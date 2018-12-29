var vaildatorRule={
    message: 'This value is not valid',
    feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
    },
    fields: {
        //表单name  
        oldPassword: {
            //提示消息  
            message: '旧密码错误',
            //需要做的验证  
            validators: {
                //验证项  
                notEmpty: {
                    message: '旧密码不能为空'
                },
                stringLength: {
                    min: 6,
                    max: 18,
                    message: '旧密码长度必须在6到18位之间'
                },
            }
        },
        password: {
            //提示消息
            message: '密码错误',
            //需要做的验证
            validators: {
                //验证项
                notEmpty: {
                    message: '密码不能为空'
                },
                stringLength: {
                    min: 6,
                    max: 18,
                    message: '密码长度必须在6到18位之间'
                },
            }
        },
        checkPassword: {
            //提示消息
            message: '两次密码填写的不一致',
            //需要做的验证
            validators: {
                //验证项
                notEmpty: {
                    message: '确认密码不能为空'
                },
                stringLength: {
                    min: 6,
                    max: 18,
                    message: '确认密码长度必须在6到18位之间'
                },
                identical: {
                    field: 'password',
                    message: '两次输入的密码不相同'
                }
            }
        },
        phone: {
            //提示消息  
            message: '手机号错误',
            //需要做的验证  
            validators: {
                //验证项  
                notEmpty: {
                    message: '手机号不能为空'
                },
                stringLength: {
                    min: 11,
                    max: 12,
                    message: '请输入正确的手机号',
                },
                digits: {
                    message: '请输入正确的手机号。'
                }
            }
        },
        phoneCode:{
            message: '验证码错误',
            //需要做的验证
            validators: {
                //验证项
                notEmpty: {
                    message: '验证码不能为空'
                }
            }
        },
        email: {
            //提示消息  
            message: '邮箱错误',
            //需要做的验证  
            validators: {
                //验证项  
                notEmpty: {
                    message: '邮箱不能为空'
                },
                emailAddress:{
                    message:'邮箱错误',
                }
            }
        },
        emailCode: {
            //提示消息
            message: '验证码错误',
            //需要做的验证
            validators: {
                //验证项
                notEmpty: {
                    message: '验证码不能为空'
                },
            }
        },
        linkName: {
            //提示消息
            message: '联系人不能为空',
            //需要做的验证
            validators: {
                //验证项
                notEmpty: {
                    message: '联系人不能为空'
                },
            }
        },
        sex: {
            //提示消息  
            message: '请选择性别',
            //需要做的验证  
            validators: {
                //验证项  
                notEmpty: {
                    message: '请选择性别'
                }
            }
        },
        linkPhone: {
            //提示消息
            message: '联系人手机号错误',
            //需要做的验证
            validators: {
                //验证项
                notEmpty: {
                    message: '请输入联系人手机号'
                },
                stringLength: {
                    min: 11,
                    max: 12,
                    message: '请输入正确的手机号',
                },
                digits: {
                    message: '请输入正确的手机号。'
                }
            }
        },
        object_company_name: {
            message: '企业名称错误',
            validators: {
                notEmpty: {
                    message: '请输入企业名称'
                },
                stringLength: {
                    max: 20,
                    message: '企业名称不能超过20字',
                },
            }
        },
        object_name: {
            message: '项目标题错误',
            validators: {
                notEmpty: {
                    message: '请输入项目标题'
                },
                stringLength: {
                    max: 20,
                    message: '项目标题不能超过20字',
                },
            }
        },
        object_trade: {
            message: '所属行业错误',
            validators: {
                notEmpty: {
                    message: '请选择所属行业'
                }
            }
        },
        object_stage: {
            message: '投资阶段错误',
            validators: {
                notEmpty: {
                    message: '请选择投资阶段'
                }
            }
        },
        object_address: {
            message: '所属地区错误',
            validators: {
                notEmpty: {
                    message: '请选择所属地区'
                }
            }
        },
        object_financing_fund: {
            message: '融资金额错误',
            validators: {
                notEmpty: {
                    message: '请输入融资金额'
                },
                digits: {
                    message: '融资金额必须为数字'
                }
            }
        },
        object_share_stock: {
            message: '出让股份错误',
            validators: {
                digits: {
                    message: '出让股份必须为数字'
                }
            }
        },
        object_website: {
            message: '项目网址错误',
            validators: {

            }
        },
        object_BP: {
            message: '项目BP资料错误',
            validators: {
            }
        },
        object_brief: {
            message: '项目亮点错误',
            validators: {
                notEmpty: {
                    message: '请输入项目亮点'
                },
                stringLength: {
                    max: 100,
                    message: '项目亮点不能超过100字',
                },

            }
        },
        object_detail: {
            message: '项目详细描述错误',
            validators: {
                notEmpty: {
                    message: '请输入项目详细描述'
                },
                stringLength: {
                    max: 500,
                    message: '详细描述不能超过500字',
                },

            }
        },
        object_img: {
            message: '项目海报错误',
            validators: {
                notEmpty: {
                    message: '请上传项目海报'
                }
            }
        },
    }
};

$("[data-action='form-check']")
    .popover({content: '请填写此字段', trigger: "focus", placement: "top"})
    .on("invalid", function () {
        $(this).addClass("invalid")
    })
    .on("input change", function () {
        $(this).removeClass("invalid")
    })
;